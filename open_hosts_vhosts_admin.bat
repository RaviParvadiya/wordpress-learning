@echo off
powershell -Command "Start-Process 'C:\Users\ravip\AppData\Local\Programs\Microsoft VS Code\Code.exe' -ArgumentList @('C:\Windows\System32\drivers\etc\hosts', 'C:\xampp\apache\conf\extra\httpd-vhosts.conf') -Verb RunAs"
