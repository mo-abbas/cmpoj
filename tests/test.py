import os
with open("result.txt", 'w') as f:
	f.write('')

for f in os.listdir('.'):
	if f.endswith('Test.php'):
		print os.system('phpunit --bootstrap vendor/autoload.php ' + f + ' >> result.txt')