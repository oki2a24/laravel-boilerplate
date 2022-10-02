import { createRouter, createWebHistory } from "vue-router";
import MyHome from "@/views/MyHome.vue";
import Swiper1 from "@/views/examples/Swiper1.vue";
import Swiper2 from "@/views/examples/Swiper2.vue";
import Swiper3 from "@/views/examples/Swiper3.vue";

const routes = [
  { path: "/", name: "MyHome", component: MyHome },
  { path: "/examples/swiper1", name: "Swiper1", component: Swiper1 },
  { path: "/examples/swiper2", name: "Swiper2", component: Swiper2 },
  { path: "/examples/swiper3", name: "Swiper3", component: Swiper3 },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
