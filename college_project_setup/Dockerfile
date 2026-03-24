FROM node:24-alpine AS builder

RUN corepack enable && corepack prepare pnpm@latest --activate

WORKDIR /app

COPY package.json pnpm-lock.yaml* ./
RUN pnpm install --frozen-lockfile

COPY . .

#production
FROM node:24-alpine

#install pnpm
RUN corepack enable && corepack prepare pnpm@latest --activate

WORKDIR /app

#copy dependencies
COPY --from=builder /app/node_modules ./node_modules
COPY --from=builder /app .

EXPOSE 3000

CMD ["pnpm", "start"]
