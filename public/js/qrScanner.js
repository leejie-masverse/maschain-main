const qrScanner = window.qrcode;

const video = document.createElement("video");
const canvasElement = document.getElementById("qr-canvas");
const canvas = canvasElement.getContext("2d");

const qrResult = document.getElementById("qr-result");
const outputData = document.getElementById("outputData");
const btnScanQR = document.getElementById("btn-scan-qr");

let scanning = false;

qrScanner.callback = res => {
    if (res) {

        if(isValidUrl(res)){
            window.open(res);
        }
        outputData.innerText = res;
        scanning = false;

        video.srcObject.getTracks().forEach(track => {
            track.stop();
        });

        qrResult.hidden = false;
        canvasElement.hidden = true;
        btnScanQR.hidden = false;
    }
};

btnScanQR.onclick = () => {
    navigator.mediaDevices
        .getUserMedia({ video: { facingMode: "environment" } })
        .then(function(stream) {
            scanning = true;
            qrResult.hidden = true;
            btnScanQR.hidden = true;
            canvasElement.hidden = false;
            video.setAttribute("playsinline",''); // required to tell iOS safari we don't want fullscreen
            video.srcObject = stream;
            video.setAttribute("style","position:absolute;opacity:0;top:0;z-index:-1000");
            document.body.appendChild(video);
            tick();
            scan();
            video.play();
        },function(error){
            alert(error);
            outputData.innerText = error;
            qrResult.hidden = false;
            canvasElement.hidden = true;
            btnScanQR.hidden = false;
        })
};

function tick() {
    canvasElement.height = canvasElement.width;
    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

    scanning && requestAnimationFrame(tick);
}

function scan() {
    try {
        qrScanner.decode();
    } catch (e) {
        setTimeout(scan, 300);
    }
}

function isValidUrl(string) {
    try {
        new URL(string);
    } catch (_) {
        return false;
    }

    return true;
}
