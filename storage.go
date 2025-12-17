package main

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"mime/multipart"
	"net/http"
	"net/url"
	"os"
	"path/filepath"
	"strings"
	"time"
)

// Storage is an abstraction over file storage backends.
type Storage interface {
	// Save stores a file and returns the public URL.
	Save(file multipart.File, header *multipart.FileHeader, prefix string) (string, error)
}

// LocalStorage stores files on the local filesystem and serves them under baseURL.
// Used for local development via Docker Compose.
type LocalStorage struct {
	BasePath string // e.g. "uploads"
	BaseURL  string // e.g. "/uploads"
}

func (s *LocalStorage) Save(file multipart.File, header *multipart.FileHeader, prefix string) (string, error) {
	dir := filepath.Join(s.BasePath, prefix)
	if err := os.MkdirAll(dir, 0755); err != nil {
		return "", fmt.Errorf("create upload dir: %w", err)
	}

	ext := strings.ToLower(filepath.Ext(header.Filename))
	filename := fmt.Sprintf("%d%s", time.Now().UnixNano(), ext)
	destPath := filepath.Join(dir, filename)

	dest, err := os.Create(destPath)
	if err != nil {
		return "", fmt.Errorf("create file: %w", err)
	}
	defer dest.Close()

	if _, err = io.Copy(dest, file); err != nil {
		return "", fmt.Errorf("write file: %w", err)
	}

	return s.BaseURL + "/" + prefix + "/" + filename, nil
}

// GCSStorage uploads files to Google Cloud Storage using the JSON REST API.
// Authentication uses the Cloud Run metadata server (no SDK dependency).
type GCSStorage struct {
	Bucket string
}

func (s *GCSStorage) Save(file multipart.File, header *multipart.FileHeader, prefix string) (string, error) {
	data, err := io.ReadAll(file)
	if err != nil {
		return "", fmt.Errorf("read upload: %w", err)
	}

	ext := strings.ToLower(filepath.Ext(header.Filename))
	objectName := prefix + "/" + fmt.Sprintf("%d%s", time.Now().UnixNano(), ext)
	contentType := header.Header.Get("Content-Type")
	if contentType == "" {
		contentType = "application/octet-stream"
	}

	token, err := gcsAccessToken()
	if err != nil {
		return "", fmt.Errorf("get access token: %w", err)
	}

	uploadURL := fmt.Sprintf(
		"https://storage.googleapis.com/upload/storage/v1/b/%s/o?uploadType=media&name=%s",
		url.PathEscape(s.Bucket),
		url.QueryEscape(objectName),
	)

	req, err := http.NewRequest("POST", uploadURL, bytes.NewReader(data))
	if err != nil {
		return "", err
	}
	req.Header.Set("Authorization", "Bearer "+token)
	req.Header.Set("Content-Type", contentType)

	resp, err := http.DefaultClient.Do(req)
	if err != nil {
		return "", fmt.Errorf("upload to GCS: %w", err)
	}
	defer resp.Body.Close()

	if resp.StatusCode >= 400 {
		body, _ := io.ReadAll(resp.Body)
		return "", fmt.Errorf("GCS upload failed %d: %s", resp.StatusCode, string(body))
	}

	// Make the object publicly readable
	_ = gcsSetPublic(s.Bucket, objectName, token)

	return fmt.Sprintf("https://storage.googleapis.com/%s/%s", s.Bucket, objectName), nil
}

// gcsSetPublic grants allUsers READ access to the object.
func gcsSetPublic(bucket, object, token string) error {
	body := `{"role":"READER","entity":"allUsers"}`
	aclURL := fmt.Sprintf(
		"https://storage.googleapis.com/storage/v1/b/%s/o/%s/acl",
		url.PathEscape(bucket),
		url.PathEscape(object),
	)
	req, err := http.NewRequest("POST", aclURL, strings.NewReader(body))
	if err != nil {
		return err
	}
	req.Header.Set("Authorization", "Bearer "+token)
	req.Header.Set("Content-Type", "application/json")
	resp, err := http.DefaultClient.Do(req)
	if err != nil {
		return err
	}
	resp.Body.Close()
	return nil
}

// gcsAccessToken retrieves a short-lived OAuth2 token from the GCE metadata server.
func gcsAccessToken() (string, error) {
	req, err := http.NewRequest("GET",
		"http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/token",
		nil)
	if err != nil {
		return "", err
	}
	req.Header.Set("Metadata-Flavor", "Google")

	resp, err := http.DefaultClient.Do(req)
	if err != nil {
		return "", fmt.Errorf("metadata server: %w", err)
	}
	defer resp.Body.Close()

	var tok struct {
		AccessToken string `json:"access_token"`
	}
	if err = json.NewDecoder(resp.Body).Decode(&tok); err != nil {
		return "", err
	}
	return tok.AccessToken, nil
}
