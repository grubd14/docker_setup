# Inventory (Node.js) Setup

This document explains how the backend and frontend in `inventory/` are containerized and how to run them together.

## Folder Structure

- `inventory/backend/`
  - `Dockerfile`
  - `docker-compose.backend.yml`
- `inventory/frontend/`
  - `Dockerfile`
  - `docker-compose.frontend.yml`
- `inventory/nodejs.md` (this file)

## High-Level Architecture

You currently have 3 services for the inventory app:

1. `postgres_db` (PostgreSQL)
2. `project_backend` (Node.js backend, container name `inventory_backend`)
3. `project_frontend` (frontend build container, container name `inventory_frontend`)

All services are expected to run on the same external Docker network: `project-network`. This project is not under caddy network.

We need to create the network once:
`docker network create project-network`

## Backend Setup
This starts:

- `postgres_db`
  - Image: `postgres:18`
  - Exposes: `5432`
  - Persists data in named volume `postgres_data`
- `project_backend`
  - Built from `backend/Dockerfile`
  - Exposes: `3000`
  - Loads env vars from `./odin-inventory-nodejs/.env`
  - Mounts source code into `/app`
  - Uses `/app/node_modules` as a container-only volume

### Dockerfile: `backend/Dockerfile`

- Multi-stage Node 24 Alpine build
- Installs dependencies using `pnpm`
- Runtime stage copies app + `node_modules`
- Container starts with `pnpm start`

## Frontend Setup
This starts:

- `project_frontend`
  - Built from `frontend/Dockerfile`
  - Maps port `5173:5173`
  - Mounts named volume `frontend_dist` at `/app/dist`
  - Depends on `project_backend`

### Dockerfile: `frontend/Dockerfile`

- Multi-stage Node 24 Alpine build
- Installs dependencies with `pnpm`
- Runs `pnpm run build` in builder stage
- Copies built `dist` into runtime image
- Final command is `tail -f /dev/null` (container stays alive, but does not serve frontend itself)


## Important Notes

1. **Database credentials are hardcoded in compose**
   - Current values are directly in `docker-compose.backend.yml`.
   - For production, move these to environment variables or secrets.

2. **Frontend container currently builds assets but does not serve them**
   - `CMD ["tail", "-f", "/dev/null"]` keeps the container alive.
   - If you want this container to serve on port `5173`, replace the command with a real frontend runtime command (for example preview/dev server) and ensure it binds to `0.0.0.0`.

3. **Service DNS names on the Docker network**
   - Backend is reachable as `project_backend` (service) or `inventory_backend` (container name)
   - Database is reachable as `postgres_db`
   - Frontend is reachable as `project_frontend` / `inventory_frontend`

