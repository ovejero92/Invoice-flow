import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('hourTimer', () => ({
        running: false,
        seconds: 0,
        timer: null,
        tareaId: '',
        horasField: '',
        start() {
            if (this.running || !this.tareaId) {
                return;
            }
            this.running = true;
            this.timer = window.setInterval(() => {
                this.seconds += 1;
            }, 1000);
        },
        stop() {
            if (!this.running) {
                return;
            }
            this.running = false;
            window.clearInterval(this.timer);
            this.timer = null;
            this.horasField = this.horasValue();
        },
        reset() {
            this.stop();
            this.seconds = 0;
            this.horasField = '';
        },
        display() {
            const h = String(Math.floor(this.seconds / 3600)).padStart(2, '0');
            const m = String(Math.floor((this.seconds % 3600) / 60)).padStart(2, '0');
            const s = String(this.seconds % 60).padStart(2, '0');

            return `${h}:${m}:${s}`;
        },
        horasValue() {
            return (this.seconds / 3600).toFixed(2);
        },
    }));
});

Alpine.start();
