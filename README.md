# sync-access-lan

#### Grant LAN users shared control of a single eWeLink account's connected smart devices. 

* Users must connected to the same LAN as the controller (physical authentication)
* Controller keeps the eWeLink credentials (never sent to a remote server besides eWeLink)
* Controller has a web interface for user dashboard


### Technical details

For installing on a stand-alone "controller" device which is on a LAN with internet access (Windows, Linux, Raspberry PI, oDroid...)
- locally hosted Node.js http server
  - eWeLink API interaction
  - communicates with the sync-access remote server 
     - announces controller network information to remote server, in order for the remote server to redirect users to the local network PHP web interface.
- locally hosted PHP website
  - custom frontend dashboard UI for user control of eWeLink devices
     - provides a proxy in order for client javascript interaction with node.js controller

