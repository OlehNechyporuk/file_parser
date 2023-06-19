FROM nginx:alpine

RUN mkdir -p /home/nginx/logs

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /home/nginx/logs/access.log \
  && ln -sf /dev/stderr /home/nginx/logs/error.log

EXPOSE 80

CMD [ "nginx" ]
