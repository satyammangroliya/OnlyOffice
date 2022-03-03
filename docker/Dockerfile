# syntax=docker/dockerfile:1
FROM docker-registry.fluxpublisher.ch/flux-ilias/ilias-base:php7.4 AS ilias

ARG GITLAB_API_KEY

# ILIAS Core
RUN cd "$ILIAS_WEB_DIR" \
    && curl -SL "https://github.com/ILIAS-eLearning/ILIAS/releases/download/v7.6/ILIAS-7.6.tar.gz" \
    | tar -xz --strip-components=1 \

# Plugin CertificateCron
RUN mkdir -p "$ILIAS_WEB_DIR/Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice" \
    && cd "$ILIAS_WEB_DIR/Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice" \
    && curl -SL "https://github.com/fluxapps/OnlyOffice/archive/refs/tags/v1.2.0.tar.gz" \
    | tar -xz --strip-components=1 \

FROM docker-registry.fluxpublisher.ch/flux-ilias/nginx-base:latest AS nginx
COPY --from=ilias $ILIAS_WEB_DIR $ILIAS_WEB_DIR

FROM docker-registry.fluxpublisher.ch/flux-ilias/cron-base:php7.4 AS cron
COPY --from=ilias $ILIAS_WEB_DIR $ILIAS_WEB_DIR

FROM docker-registry.fluxpublisher.ch/flux-ilias/ilserver-base:java8 AS ilserver
COPY --from=ilias $ILIAS_WEB_DIR $ILIAS_WEB_DIR

FROM onlyoffice/communityserver as onlyoffice
