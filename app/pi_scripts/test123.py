#!/usr/bin/env python
import sys
import os

print "Argument", sys.argv[1]
os.system("sudo date -s \"%s\"" % sys.argv[1])
os.system("sudo hwclock -w")

