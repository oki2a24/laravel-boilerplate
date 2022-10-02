<template>
  <BaseSwiper :images="images" />
</template>

<script>
export default {
  name: "ExamplesSwiper1",
};
</script>
<script setup>
import BaseSwiper from "@/components/BaseSwiper.vue";

const images = [
  "https://i.picsum.photos/id/925/600/400.jpg?hmac=VxvBfviRrdHXsJ6_nbZ00wdqs0AEutxmI1ughFU8KQU",
  "https://i.picsum.photos/id/428/600/400.jpg?hmac=grP0p8M-3MohHSsDfu7ZXo9VGbL-OUSCDScCUZu8ps4",
  "https://i.picsum.photos/id/340/600/400.jpg?hmac=fUPfZKTRNEKns8xR8vPzDByTCfY9cEbh5lFLG8rP8H8",
  "https://i.picsum.photos/id/702/600/400.jpg?hmac=KxRAc7VlNyemdt4RTHqjBvy9heWN7FV0qHG_U2OyOjo",
  "https://picsum.photos/id/139/800/500",
  "https://picsum.photos/id/1060/800/500",
  "https://picsum.photos/id/1023/800/500",
  "https://picsum.photos/id/1047/800/500",
  "https://picsum.photos/id/248/800/500",
  "https://picsum.photos/id/109/800/500",
];
</script>
