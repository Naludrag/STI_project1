FROM naludrag/sti_project_2:latest

COPY --chown=www-data ./site/ /usr/share/nginx/

RUN echo "exit 0" > /usr/sbin/policy-rc.d
CMD /etc/init.d/php5-fpm restart && nginx -g "daemon off;"

EXPOSE 80