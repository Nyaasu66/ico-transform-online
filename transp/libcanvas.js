/**
 * libcanvas.js v0.1.0
 * author: Yosiya Hinosawa ( @kt3k )
 * license: MIT License ( http://kt3k.mit-license.org/ )
 * url: https://github.com/kt3k/libcanvas
 */

this.libcanvas = this.exports = (function () {

    var exports = {};


    exports.createCanvasBasedOnImg = function (img) {
        var canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        exports.pasteImg(canvas, img);

        return canvas;
    };


    exports.pasteImg = function (canvas, img) {
        canvas.getContext('2d').globalCompositeOperation = 'copy';
        canvas.getContext('2d').drawImage(img, 0, 0);
    };

    exports.downloadablePng = function (canvas) {
        return canvas.toDataURL("image/png").replace("image/png", "image/octet-stream")
    };

    exports.downloadAsPng = function (canvas) {
        window.location.href = exports.downloadablePng(canvas);
    };

    exports.roundCanvas = function (canvas, radius) {
        radius = radius;
        exports.cutRoundedRect(canvas.getContext('2d'), radius, 0, 0, canvas.width, canvas.height);
    };

    exports.cutRoundedRect = function(ctx, radius, x, y, w, h) {
        var left = x;
        var top = y;
        var right = x + w;
        var bottom = y + h;

        ctx.globalCompositeOperation = 'destination-in';
        ctx.fillStyle = 'black';

        ctx.beginPath();
        ctx.moveTo(left + radius, top);
        ctx.lineTo(right - radius, top);
        ctx.quadraticCurveTo(right, top, right, top + radius);
        ctx.lineTo(right, bottom - radius);
        ctx.quadraticCurveTo(right, bottom, right - radius, bottom);
        ctx.lineTo(left + radius, bottom);
        ctx.quadraticCurveTo(left, bottom, left, bottom - radius);
        ctx.lineTo(left, top + radius);
        ctx.quadraticCurveTo(left, top, left + radius, top);
        ctx.fill();
    };

    return exports;

}());
