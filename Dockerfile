# --- Build stage ---
FROM golang:1.22-alpine AS builder

WORKDIR /app

# Download dependencies first (cached layer)
COPY go.mod go.sum ./
RUN go mod download

# Copy source and embed assets
COPY . .

# Build a static binary
RUN CGO_ENABLED=0 GOOS=linux go build -ldflags="-s -w" -o vc19 .

# --- Runtime stage ---
FROM alpine:3.19

# ca-certificates needed for TLS connections to external services (GCS, Neon, etc.)
RUN apk --no-cache add ca-certificates tzdata

WORKDIR /app

COPY --from=builder /app/vc19 .

# Cloud Run sets PORT env var; default to 8080
ENV PORT=8080

EXPOSE 8080

CMD ["./vc19"]
