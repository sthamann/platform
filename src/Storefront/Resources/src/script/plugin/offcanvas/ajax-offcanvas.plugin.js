import OffCanvas, { OffcanvasInstance } from 'src/script/plugin/offcanvas/offcanvas.plugin';
import HttpClient from 'src/script/service/http-client.service';
import LoadingIndicator from 'src/script/utility/loading-indicator/loading-indicator.util';

// xhr call storage
let xhr = null;

export default class AjaxOffCanvas extends OffCanvas {

    /**
     * Fire AJAX request to get the offcanvas content
     *
     * @param {string} url
     * @param {*|boolean} data
     * @param {function|null} callback
     * @param {'left'|'right'} position
     * @param {boolean} closable
     * @param {number} delay
     * @param {boolean} fullwidth
     */
    static open(url = false, data = false, callback = null, position = 'left', closable = true, delay = OffCanvas.REMOVE_OFF_CANVAS_DELAY, fullwidth = false) {
        if (!url) {
            throw new Error('A url must be given!');
        }
        // avoid multiple backdrops
        OffcanvasInstance._removeExistingOffCanvas();

        const offCanvas = OffcanvasInstance._createOffCanvas(position, fullwidth);
        this.setContent(url, data, callback, closable, delay);
        OffcanvasInstance._openOffcanvas(offCanvas);
    }

    /**
     * Method to change the content of the already visible OffCanvas via xhr
     *
     * @param {string} url
     * @param {*} data
     * @param {function} callback
     * @param {boolean} closable
     * @param {number} delay
     */
    static setContent(url, data, callback, closable, delay) {
        const client = new HttpClient(window.accessKey, window.contextToken);
        super.setContent(LoadingIndicator.getTemplate(), closable, delay);

        // interrupt already running ajax calls
        if (xhr) xhr.abort();

        const cb = (response) => {
            super.setContent(response, closable, delay);
            // if a callback function is being injected execute it after opening the OffCanvas
            if (typeof callback === 'function') {
                callback();
            }
        };

        if (data) {
            xhr = client.post(url, data, cb);
        } else {
            xhr = client.get(url, cb);
        }
    }
}
