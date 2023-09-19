FROM node:lts

WORKDIR /app/website

EXPOSE 3000 35729
COPY ./doc /app/docs
COPY ./doc/docs /app/website

RUN yarn install

CMD ["yarn", "start"]