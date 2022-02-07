'use strict';

const fetch = require('node-fetch');
const ip = require("ip");


const {
  networkInterfaces
} = require('os');

const config = require('../config.json');
// override default config with any set arguments
argsToConfig();

var fork = require('child_process').fork;
var child = fork('./script');


console.log("announce.js started...");
console.log();
console.log("############");
console.log("Announcing this device's internal + external IP's to remote IP address mapper: " + config.serverAPI + " every " + config.refreshInterval + " seconds");
console.log("############");


detectAndAnnounce();

//do something when app is closing
process.on('exit', exitHandler.bind(null, {
  cleanup: true
}));

//catches ctrl+c event
process.on('SIGINT', exitHandler.bind(null, {
  exit: true
}));

// catches "kill pid" (for example: nodemon restart)
process.on('SIGUSR1', exitHandler.bind(null, {
  exit: true
}));
process.on('SIGUSR2', exitHandler.bind(null, {
  exit: true
}));

//catches uncaught exceptions
process.on('uncaughtException', exitHandler.bind(null, {
  exit: true
}));

// =====================
// ===   FUNCTIONS   ===
// =====================

function detectNetwork() {
  /*
  //   const nets = networkInterfaces();
  //   const results = Object.create(null); // Or just '{}', an empty object
  //
  //   for (const name of Object.keys(nets)) {
  //     for (const net of nets[name]) {
  //       // Skip over non-IPv4 and internal (i.e. 127.0.0.1) addresses
  //       if (net.family === 'IPv4' && !net.internal) {
  //         if (!results[name]) {
  //           results[name] = [];
  //         }
  //         results[name].push(net.address);
  //       }
  //     }
  //   }
  // console.log(nets);
  //
  //   var ip = null;
  //   var netInterface = null;
  //   if (results['eth0']) {
  //     netInterface = "eth0";
  //     ip = results['eth0'][0];
  //   } else if (results['Wi-Fi']) {
  //     netInterface = "Wi-Fi";
  //     ip = results['Wi-Fi'][0];
  //   }
  //
  //   // save to config
  //   config.host.network.ip = ip;
  //   config.host.network.interface = netInterface;
  */
  config.host.network.ip = ip.address();
  return {
    "ip": ip
  };
}

async function announce() {
  const currentDatetimeString = getCurrentDatetimeString();
  if (config.host.network.ip) {
    console.log();
    console.log("# # # " + currentDatetimeString + " # # #");
    console.log("# Device's current LAN connection: " + config.host.network.ip);
    console.log("# Announcing IPs to server...");

    const req_body = {
      "host": config.host
    };
    try {
      const ms = Date.now();
      const response = await fetch(config.serverAPI, {
        method: 'post',
        body: JSON.stringify(req_body),
        headers: {
          'Content-Type': 'application/json',
          'pragma': 'no-cache',
          'Cache-Control': 'no-cache'
        }
      });
      try {
        const data = await response.json();

        console.log('# response:');
        console.log(data);
      } catch (e) {
        console.log('\x1b[31m%s\x1b[0m', e); //red font
      } finally{
        setTimeout(() => {
          detectAndAnnounce();
        }, (config.refreshInterval * 1000));
      }
    } catch (e) {
      console.log('\x1b[31m%s\x1b[0m', e); //red font

    }


  } else {
    console.log();
    console.log("!!! " + currentDatetimeString);
    console.log("!!! Network disconnected");
  }
}

function detectAndAnnounce() {
  detectNetwork();
  announce();
}

/* OTHER FUNCTIONS */
function getCurrentDatetimeString() {
  const now = new Date();
  const offsetMs = now.getTimezoneOffset() * 60 * 1000;
  const dateLocal = new Date(now.getTime() - offsetMs);
  const timeString = dateLocal.toISOString().slice(0, 19).replace(/-/g, "/").replace("T", " ");
  return timeString;
}

function argsToConfig() {
  const args = process.argv.slice(2);
  for (var i = 0; i < args.length; i++) {
    if (args[i].indexOf('id=') > -1) {
      config.host.id = args[i].substring(args[i].indexOf('id=') + 'id='.length);
    }
    if (args[i].indexOf('secret=') > -1) {
      config.host.secret = args[i].substring(args[i].indexOf('secret=') + 'secret='.length);
    }
    if (args[i].indexOf('interval=') > -1) {
      config.refreshInterval = args[i].substring(args[i].indexOf('interval=') + 'interval='.length);
    }
  }
};

function exitHandler(options, exitCode) {
  if (options.cleanup) console.log('\x1b[33m%s\x1b[0m', 'Performing cleanup, terminating child processes...'); //yellow font
  if (exitCode || exitCode === 0) console.log('\x1b[1;37m\x1b[1;41m%s\x1b[0m', "exitCode: " + exitCode);
  if (options.exit) process.exit();
}
