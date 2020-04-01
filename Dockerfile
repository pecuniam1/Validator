FROM node:10

WORKDIR C:/Users/kevjo/Documents/workspace/Validation/classes/
COPY package*.json ./

RUN npm install

COPY . .

EXPOSE 8081
CMD [ "node", "server.js" ]