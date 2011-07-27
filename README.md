# LightMonitor

Welcome to the lightMonitor wiki!

The goal of this project : Create a light monitor with php to view status of servers.

Protocols to ask informations to the servers :

- [SSH](http://fr.wikipedia.org/wiki/Ssh)
- [SNMP](http://fr.wikipedia.org/wiki/Snmp)
- [HTTP](http://fr.wikipedia.org/wiki/Hypertext_Transfer_Protocol)

With this, a jobcron will be create to generate [RRDTOOL](http://www.mrtg.org/rrdtool/) graphics and an other to check if servers response to ping action.


This software is based on the micro-framework [Silex](http://silex-project.org/).