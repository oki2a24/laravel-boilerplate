import { createRouter, createWebHistory } from "vue-router";
import MyHome from "@/views/MyHome.vue";
import MyLogin from "@/views/MyLogin.vue";

const routes = [
  { path: "/", name: "MyHome", component: MyHome },
  { path: "/login", name: "MyLogin", component: MyLogin },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
