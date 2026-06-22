import { createInertiaApp } from "@inertiajs/vue3";
import { ZiggyVue } from "ziggy-js";
import DashboardLayout from "@/layout/DashboardLayout.vue";

createInertiaApp({
    layout: () => DashboardLayout,
    withApp(app) {
        app.use(ZiggyVue);
    },
});
