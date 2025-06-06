# Imagen base
FROM node:18-alpine

# Directorio de trabajo
WORKDIR /app

# Copiar dependencias e instalarlas
COPY package.json package-lock.json ./
RUN npm install

# Copiar el resto del código
COPY . .

# Puerto expuesto (Vite usa 5173 por defecto)
EXPOSE 5173

# Comando de inicio (ajusta según tu framework)
CMD ["npm", "run", "dev"]