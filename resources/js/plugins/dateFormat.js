export default {
    install: (app, options) => {
        app.config.globalProperties.$dateTime = dateString => {
            return new Date(dateString).toLocaleString('de-DE', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            }) + ' Uhr'
        }
    }
}
