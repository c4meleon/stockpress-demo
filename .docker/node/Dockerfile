FROM node:18-alpine

WORKDIR /app

COPY ./frontend/package*.json ./

RUN npm install

COPY ./frontend /app

RUN npm run build

EXPOSE 3000

CMD ["npm", "start"]

# CMD ["npx", "serve", "-s", "build", "-l", "3000"]
