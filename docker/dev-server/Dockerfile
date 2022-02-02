FROM node as node

ARG WEB_PATH

COPY ./web $WEB_PATH

WORKDIR $WEB_PATH

RUN yarn

ENTRYPOINT ["yarn", "start"]