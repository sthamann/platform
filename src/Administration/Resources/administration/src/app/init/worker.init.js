import AdminWorker from 'src/core/worker/admin-worker.worker';
import WorkerNotificationListener from 'src/core/worker/worker-notification-listener';

/**
 * Starts the worker
 */
export default function initializeWorker() {
    const configService = this.getContainer('service').configService;

    configService.getConfig().then((response) => {
        if (response.adminWorker.enableAdminWorker) {
            const loginService = this.getContainer('service').loginService;
            const context = this.getContainer('init').contextService;

            enableAdminWorker(loginService, context, response.adminWorker);
            enableWorkerNotificationListener(
                loginService,
                context
            );
        }
    });
}

function enableAdminWorker(loginService, context, config) {
    let worker = getWorker(loginService);

    if (loginService.isLoggedIn()) {
        worker.postMessage({
            context,
            bearerAuth: loginService.getBearerAuthentication(),
            host: window.location.origin,
            transports: config.transports
        });
    }

    loginService.addOnTokenChangedListener((auth) => {
        worker.terminate();
        worker = getWorker(loginService);
        worker.postMessage({
            context,
            bearerAuth: auth,
            host: window.location.origin,
            transports: config.transports
        });
    });

    loginService.addOnLogoutListener(() => {
        worker.terminate();
        worker = getWorker(loginService);
    });
}

function getWorker(loginService) {
    const worker = new AdminWorker();

    worker.onmessage = () => {
        loginService.refreshToken();
    };

    return worker;
}

function enableWorkerNotificationListener(loginService, context) {
    let workerNotificationListener = new WorkerNotificationListener(loginService, context);

    if (loginService.isLoggedIn()) {
        workerNotificationListener.start(5000);
    }

    loginService.addOnTokenChangedListener(() => {
        workerNotificationListener.terminate();
        workerNotificationListener = new WorkerNotificationListener(loginService, context);
        workerNotificationListener.start(5000);
    });

    loginService.addOnLogoutListener(() => {
        workerNotificationListener.terminate();
        workerNotificationListener = new WorkerNotificationListener(loginService, context);
    });
}
