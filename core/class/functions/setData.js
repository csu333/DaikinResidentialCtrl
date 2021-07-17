/**
 * 
 */

const DaikinCloud = require('../../daikin-controller-cloud/index');
const fs = require('fs');
const path = require('path');

async function main() {
    /**
     * Options to initialize the DaikinCloud instance with
     */
    const options = {
        logger: console.log,          // optional, logger function used to log details depending on loglevel
        logLevel: 'info',             // optional, Loglevel of Library, default 'warn' (logs nothing by default)
    };

    let tokenSet;
    const tokenFile = path.join(__dirname, 'tokenset.json');

    var myArgs = process.argv.slice(2);

    if (myArgs.length < 3) {
        console.log('No enough arguments. Use is getData <devID> <control type> <control name> <value> <JSON token>');
        process.exitCode = 2;
        return;
    }

    var devId = myArgs[0];
    var ctrlType = myArgs[1];
    try {
        tokenSet = JSON.parse(myArgs[myArgs.length - 1]);
        console.log("Token found in arguments");
        if (myArgs.length == 4) {
            var value = myArgs[2];
        } else {
            var ctrlName = myArgs[2];
            var value = myArgs[3];
        }
    } catch {
        if (myArgs[myArgs.length - 1].length == 0) {
            console.log("Removing last argument");
            myArgs.splice(myArgs.length - 1, 1);
        }
    }
    if (tokenSet == null) {
        // Load Tokens if they already exist on disk
        if (fs.existsSync(tokenFile)) {
            tokenSet = JSON.parse(fs.readFileSync(tokenFile).toString());
            console.log("Token found in file");
        }
        if (myArgs.length == 3) {
            var value = myArgs[2];
        } else {
            var ctrlName = myArgs[2];
            var value = myArgs[3];
        }
    }

    //console.log('ctrlName is ' + ctrlName);

    if (tokenSet == null) {
        console.log('Error getting data with arguments: ' + myArgs);
        process.exitCode = 3;
        return;
    }

    // Initialize Daikin Cloud Instance
    const daikinCloud = new DaikinCloud(tokenSet, options);

    // Event that will be triggered on new or updated tokens, save into file
    daikinCloud.on('token_update', tokenSet => {
        fs.writeFileSync(tokenFile, JSON.stringify(tokenSet));
    });

    const daikinDeviceDetails = await daikinCloud.getCloudDeviceDetails();

    const devices = await daikinCloud.getCloudDevices();

    if (devices && devices.length) {
        for (dev of devices) {
            if (dev.getId() == devId){
                if (ctrlName != null) {
                    await dev.setData('climateControl', ctrlType, ctrlName, value);
                } else {
                    await dev.setData('climateControl', ctrlType, value);
                }
            }
        }
    }
    console.log(JSON.stringify(tokenSet));
}

(async () => {
    await main();
    process.exit();
})();
