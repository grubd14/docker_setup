#!/bin/bash

# Start Caddy first, then allow choosing which service stack to start.

set -u

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Pick docker compose command (plugin first, fallback to legacy docker-compose)
if docker compose version >/dev/null 2>&1; then
  COMPOSE_CMD=(docker compose)
elif command -v docker-compose >/dev/null 2>&1; then
  COMPOSE_CMD=(docker-compose)
else
  echo "Error: neither 'docker compose' nor 'docker-compose' is available."
  exit 1
fi

run_compose() {
  local file_path="$1"
  shift
  "${COMPOSE_CMD[@]}" -f "$file_path" "$@"
}

ensure_caddy_network() {
  if ! docker network inspect caddy >/dev/null 2>&1; then
    echo "Creating external Docker network: caddy"
    docker network create caddy >/dev/null
  fi
}

start_caddy() {
  local caddy_compose="$SCRIPT_DIR/docker-compose.yml"

  if [ ! -f "$caddy_compose" ]; then
    echo "Error: Caddy compose file not found at: $caddy_compose"
    return 1
  fi

  ensure_caddy_network

  echo "Starting Caddy first..."
  run_compose "$caddy_compose" up -d caddy
}

start_wireguard() {
  local file="$SCRIPT_DIR/wireguard/docker-compose.yml"
  if [ ! -f "$file" ]; then
    echo "Wireguard compose file not found: $file"
    return 1
  fi
  run_compose "$file" up -d
}

start_nextcloud() {
  local file="$SCRIPT_DIR/nextcloud/docker-compose.yml"
  if [ ! -f "$file" ]; then
    echo "Nextcloud compose file not found: $file"
    return 1
  fi
  run_compose "$file" up -d
}

start_forgejo() {
  local file="$SCRIPT_DIR/forgejo/docker-compose.yml"
  if [ ! -f "$file" ]; then
    echo "Forgejo compose file not found: $file"
    return 1
  fi
  run_compose "$file" up -d
}

# Always start Caddy first
if ! start_caddy; then
  echo "Failed to start Caddy. Exiting."
  exit 1
fi

echo "Caddy started."

while true; do
  clear
  echo "--- START SERVICE ---"
  echo "1) START wireguard"
  echo "2) START nextcloud"
  echo "3) START forgejo"
  echo "4) Exit"
  read -r -p "Enter choice [1-4]: " choice

  case "$choice" in
    1)
      if start_wireguard; then
        echo "Wireguard started successfully."
      else
        echo "Failed to start Wireguard."
      fi
      read -r -p "Press Enter to continue..." ;;
    2)
      if start_nextcloud; then
        echo "Nextcloud started successfully."
      else
        echo "Failed to start Nextcloud."
      fi
      read -r -p "Press Enter to continue..." ;;
    3)
      if start_forgejo; then
        echo "Forgejo started successfully."
      else
        echo "Failed to start Forgejo."
      fi
      read -r -p "Press Enter to continue..." ;;
    4)
      echo "Goodbye."
      exit 0 ;;
    *)
      echo "Invalid option"
      sleep 1 ;;
  esac
done
