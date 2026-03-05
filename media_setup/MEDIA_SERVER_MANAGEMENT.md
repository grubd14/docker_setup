This compose file defines a small media and utility stack. Below is a high-level overview, usage notes, and an example .env snippet you can use to configure it.

Overview
- Network
  - arr_network: a single custom bridge network used by most "arr" services to communicate.

- Services (summary)
  - bentopdf
    - Image: bentopdf/bentopdf-simple:latest
    - Exposes 8080 inside container -> mapped to host via BENTO_PORT
    - Purpose: PDF utilities
  - prowlarr
    - Image: lscr.io/linuxserver/prowlarr:latest
    - Configuration stored in ${CONFIG_PATH}/prowlarr
    - Port mapped to PROWLARR_PORT (internal 9696)
    - On arr_network
    - Used as an indexer manager for other "arr" apps
  - radarr
    - Image: lscr.io/linuxserver/radarr:latest
    - Config: ${CONFIG_PATH}/radarr
    - Media mount: ${MEDIA_PATH} mounted at /data
    - Port: RADARR_PORT -> 7878
    - Depends on: prowlarr
    - On arr_network
  - sonarr
    - Image: lscr.io/linuxserver/sonarr:latest
    - Config: ${CONFIG_PATH}/sonarr
    - Media mount: ${MEDIA_PATH} at /data
    - Port: SONARR_PORT -> 8989
    - Depends on: prowlarr
    - On arr_network
  - bazarr
    - Image: lscr.io/linuxserver/bazarr:latest
    - Config: ${CONFIG_PATH}/bazarr
    - Media mount: ${MEDIA_PATH} at /data
    - Port: BAZARR_PORT -> 6767
    - Depends on: radarr, sonarr
    - On arr_network
  - jellyfin
    - Image: lscr.io/linuxserver/jellyfin:latest
    - Config: ${CONFIG_PATH}/jellyfin
    - Media mount: ${MEDIA_PATH} at /data
    - Port: JELLYFIN_PORT -> 8096
    - On arr_network
  - qbittorrent
    - Image: lscr.io/linuxserver/qbittorrent:latest
    - Config: ${CONFIG_PATH}/qbittorrent
    - Downloads: ${DOWNLOAD_PATH} mounted at /data/downloads
    - Ports: QB_PORT and QB_WEBUI_PORT (plus 6881 TCP/UDP)
    - Note: WEB UI port is configured via WEBUI_PORT environment variable inside container
    - On arr_network
  - qui
    - Image: ghcr.io/autobrr/qui:latest
    - Config: ${CONFIG_PATH}/qui
    - Exposes 7476 (mapped to host 7476)
  - homarr
    - Image: ghcr.io/homarr-labs/homarr:latest
    - Config: ${CONFIG_PATH}/homarr/appdata
    - Optional docker integration: /var/run/docker.sock is mounted (only if you want it)
    - SECRET_ENCRYPTION_KEY is already set in compose
    - Port: HOMARR_PORT on host mapped to same container port
  - flaresolverr
    - Image: ghcr.io/flaresolverr/flaresolverr:latest
    - Port: FLARE_PORT -> 8191 inside container
    - Environment: LOG_LEVEL, LOG_HTML, CAPTCHA_SOLVER, TZ
    - Purpose: resolve anti-bot challenges for scrapers / indexers

Common notes and important details
- Volumes and SELinux
  - Some volume mounts use :Z or :z suffixes which are SELinux context options used by the linuxserver images. Keep them if you run SELinux-enabled hosts.
- User IDs and permissions
  - Many services use PUID and PGID. Set these in your .env so containers run with appropriate permissions to read/write the host mounts.
- Restart policy
  - All services use restart: unless-stopped so they automatically restart on host reboot.
- Network
  - The arr_network custom bridge is used to allow related services to communicate internally. Ports are still published to the host as configured.
- Container names
  - Each service defines container_name (e.g. prowlarr, radarr, sonarr, etc.) which makes docker commands simpler (docker logs radarr).
- Dependencies
  - radarr and sonarr depend on prowlarr; bazarr depends on both radarr and sonarr. depends_on only controls start order, it does not wait for service health checks.
- Ports
  - Replace the environment vars (e.g. RADARR_PORT, SONARR_PORT) in your .env with the desired host ports. Example: if RADARR_PORT=7878 then the container 7878 will be available at http://host:7878.

Example .env snippet
PUID=1000
PGID=1000
TZ=Europe/London
CONFIG_PATH=/path/to/appdata
MEDIA_PATH=/path/to/media
DOWNLOAD_PATH=/path/to/downloads
# Ports (examples)
BENTO_PORT=8080
PROWLARR_PORT=9696
RADARR_PORT=7878
SONARR_PORT=8989
BAZARR_PORT=6767
JELLYFIN_PORT=8096
QB_PORT=6881
QB_WEBUI_PORT=8081
HOMARR_PORT=7575
FLARE_PORT=8191
LOG_LEVEL=info
LOG_HTML=false
CAPTCHA_SOLVER=none

Quick start
1) Place a .env file next to the docker-compose.yml with values similar to the example above.
2) Start the stack:
   docker compose up -d
3) Check status:
   docker compose ps
4) View logs:
   docker compose logs -f <service_name>
5) Stop:
   docker compose down
