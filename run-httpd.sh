#!/bin/bash

# Make sure we're not confused by old, incompletely-shutdown httpd
rm -rf /run/httpd/* /tmp/httpd*
/usr/sbin/httpd -DFOREGROUND
