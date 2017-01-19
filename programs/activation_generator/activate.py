import hashlib
import sys

print " ------------------------------- "
print "AEC Electronics IBS PI Activation"
print " ------------------------------- "

keylen = 5

client_key = raw_input('Enter the Client Key :')

secret = "234lkjh234l123345lkjh345lKHWRE";

m = hashlib.sha512()
m.update(client_key + secret)

print "Activation code is: "
print m.hexdigest()[:keylen]

print "Press any key to continue"
sys.stdin.read(1)
sys.exit()
