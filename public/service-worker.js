self.addEventListener('push', (event) => {
    const notificationData = event.data ? event.data.json() : {};

    event.waitUntil(
        self.registration.showNotification(notificationData.title || 'Notification', {
            body: notificationData.body || 'You have a new notification.',
            icon: notificationData.icon || '/images/icon.png',
            data: notificationData.data || {},
            actions: notificationData.actions || []
        })
    );

});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action) {
        // Handle action button click
        console.log('Action clicked:', event.action);
    } else {
        // Handle notification click
        console.log('Notification clicked');
    }

    event.waitUntil(
        clients.openWindow(event.notification.data.url || '/')
    );
});
