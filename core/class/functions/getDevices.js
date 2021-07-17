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

    try {
        if (myArgs.length == 1) {
            tokenSet = JSON.parse(myArgs[0]);
        }
    } catch {}

    if (tokenSet == null) {
        // Load Tokens if they already exist on disk
        if (fs.existsSync(tokenFile)) {
            tokenSet = JSON.parse(fs.readFileSync(tokenFile).toString());
        }
    }

    if (tokenSet == null) {
        console.log('No token found.');
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

    var temp = '';

    if (devices && devices.length) {
        for (dev of devices) {
            console.log(dev.getId());
        }
    }
    console.log(JSON.stringify(tokenSet));
}

(async () => {
    await main();
    process.exit();
})();
