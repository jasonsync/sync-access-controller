# sync-access-lan-ewelink-controller

- Node.js controller 
  - eWeLink API interaction
  - communicates with the sync-access remote server 
     - announces controller network information to remote server, in order for the remote server to redirect users to the local network PHP web interface.
- PHP website
  - custom frontend dashboard UI for user control of eWeLink devices
     - provides a proxy in order for client javascript interaction with node.js controller

