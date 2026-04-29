#this docker file will include two separate stage for building and prod
FROM node:24-alpine AS builder

#installing pnpm
RUN corepack enable && corepack prepare pnpm@latest --activate

#the default app directory in docker
WORKDIR /app

#copy package files
COPY package.json  pnpm-lock.yaml* ./

#install dependecies
RUN pnpm install --frozen-lockfile

#copying application code
COPY . .

#build the application
RUN pnpm run build

#production stage
FROM node:24-alpine

WORKDIR /app

COPY --from=builder /app/dist  ./dist

# Keep container running (Caddy will serve the files)
CMD ["tail", "-f", "/dev/null"]