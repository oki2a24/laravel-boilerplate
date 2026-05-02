<template>
  <div class="l-wrapper">
    <main>
      <div id="demo08" class="gallery02 l-section">
        <div class="l-inner">
          <!-- スライドショー -->
          <swiper
            :effect="'fade'"
            :fade-effect="{ crossFade: true }"
            :speed="500"
            :navigation="{
              nextEl: '.gallery02 .swiper-button-next',
              prevEl: '.gallery02 .swiper-button-prev',
            }"
            :modules="modules"
            @swiper="onSwiper"
            @slide-change="onSlideChange"
          >
            <swiper-slide v-for="(image, index) in computedImages" :key="image.index">
              <figure class="slide">
                <div class="slide-media"><img :src="image.value" alt="" /></div>
                <figcaption class="slide-title">index: {{ index }} 番目の画像。</figcaption>
              </figure>
            </swiper-slide>
            <template #container-end>
              <div class="swiper-controller">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
              </div>
            </template>
          </swiper>

          <!-- サムネイル -->
          <div class="thumb-wrapper">
            <div
              v-for="image in computedImages"
              :key="image.index"
              class="thumb-media"
              :class="image.classObject"
              @click="onClickThumb(image.index)"
            >
              <img :src="image.value" alt="" />
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import "swiper/css";
import "swiper/css/effect-fade";
import "swiper/css/navigation";
import { EffectFade, Navigation } from "swiper";
import { Swiper, SwiperSlide } from "swiper/vue";
import { computed, ref } from "vue";

const props = defineProps({
  images: {
    type: Array,
    default: () => [],
    required: false,
  },
});

/*
 * スライドショーとサムネイルの連携用
 */
// スライドショーで使用している Swiper インスタンス
const mySwiper = ref(null);
// スライドショーを操作した時、その変更内容が mySwiper になぜか伝播されなかったため realIndex を別途用意する。
const swiperRealIndex = ref(null);

/*
 * `<template>` で使用するスライドショーとサムネイル用に算出した props 画像
 */
const computedImages = computed(() =>
  props.images.map((value, index) => ({
    index,
    value,
    classObject: { "thumb-media-active": index === swiperRealIndex.value },
  })),
);

/*
 * スライドショー
 */
const modules = [EffectFade, Navigation];
const onSwiper = (swiper) => {
  mySwiper.value = swiper;
  swiperRealIndex.value = swiper.realIndex;
};
const onSlideChange = (swiper) => {
  swiperRealIndex.value = swiper.realIndex;
};

/*
 * サムネイル
 */
const onClickThumb = (index) => {
  mySwiper.value.slideTo(index);
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Spartan:wght@400;700&display=swap");
:root {
  --easing: cubic-bezier(0.2, 1, 0.2, 1);
  --transition: 0.8s var(--easing);
  --color-base: #f8f8f8;
  --color-gray: #ddd;
  --color-theme: #f5695f;
  --color-theme-darken: #f12617;
  --box-shadow: 0.8rem 0.8rem 1.2rem rgba(0, 0, 0, 0.05), -0.8rem -0.8rem 1.2rem #fff;
  --box-shadow-hover: 1rem 1rem 1.5rem rgba(0, 0, 0, 0.08), -1rem -1rem 1.5rem #fff;
  --box-shadow-inset: inset 0.8rem 0.8rem 1.2rem rgba(0, 0, 0, 0.05), inset -0.8rem -0.8rem 1.2rem #fff;
  --box-shadow-dark: 0.8rem 0.8rem 1.2rem rgba(0, 0, 0, 0.1), -0.8rem -0.8rem 1.2rem rgba(#fff, 0.2);
}

html {
  font-family: "Spartan", "游ゴシック体", YuGothic, "游ゴシック", "Yu Gothic", "メイリオ", Meiryo, sans-serif;
  font-size: 62.5%;
  line-height: 1.8;
  height: 100%;
  word-break: break-word;
  color: #333;
  background-color: var(--color-base);
  -webkit-appearance: none;
  -webkit-tap-highlight-color: transparent;
}

body {
  font-size: 1.6rem;
  margin: 0;
}

*,
*::before,
*::after {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}

::-moz-selection {
  color: #fff;
  background: var(--color-theme);
}

::selection {
  color: #fff;
  background: var(--color-theme);
}

img {
  border: 0;
  margin: 0;
}

figure {
  margin: 0;
}

p {
  margin: 0;
  padding: 0;
}

a {
  text-decoration: none;
  color: #333;
}

ul,
ol {
  margin: 0;
  padding: 0;
  list-style: none;
}

h1,
h2,
h3,
h4,
h5,
h6 {
  font-size: 1.6rem;
  margin: 0;
  padding: 0;
}

main {
  display: block;
}

.l-inner {
  position: relative;
  -webkit-box-sizing: content-box;
  box-sizing: content-box;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 10rem;
}

.l-section {
  border-top: 1px solid #eee;
}
.l-section .l-inner {
  padding-top: 8rem;
  padding-bottom: 8rem;
}

[class*="swiper"]:focus {
  outline: none;
}

.slide-media,
.thumb-media {
  position: relative;
  overflow: hidden;
}
.slide-media img,
.thumb-media img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  -o-object-fit: cover;
  object-fit: cover;
}

.swiper-button-prev,
.swiper-button-next {
  display: grid;
  place-content: center;
  width: 6.4rem;
  height: 6.4rem;
  cursor: pointer;
  -webkit-transition: var(--transition);
  transition: var(--transition);
}
.swiper-button-prev::before,
.swiper-button-next::before {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  content: "";
  border-radius: 50%;
  -webkit-box-shadow: var(--box-shadow);
  box-shadow: var(--box-shadow);
}
.swiper-button-prev::after,
.swiper-button-next::after {
  width: 1.2rem;
  height: 1.2rem;
  content: "";
  border: solid var(--color-gray);
  border-width: 3px 3px 0 0;
}
.swiper-button-prev::after {
  margin-left: 0.4rem;
  -webkit-transform: rotate(-135deg);
  transform: rotate(-135deg);
}
.swiper-button-next::after {
  margin-right: 0.4rem;
  -webkit-transform: rotate(45deg);
  transform: rotate(45deg);
}
.swiper-button-disabled {
  pointer-events: none;
  opacity: 0;
}

.gallery02 {
  overflow: hidden;
}
.gallery02 .swiper,
.gallery02 .thumb-wrapper {
  max-width: 720px;
  margin: auto;
}
.gallery02 .swiper {
  overflow: visible;
}
.gallery02 .swiper-fade .swiper-slide {
  -webkit-transition-property:
    opacity,
    -webkit-transform !important;
  transition-property:
    opacity,
    -webkit-transform !important;
  transition-property: opacity, transform !important;
  transition-property:
    opacity,
    transform,
    -webkit-transform !important;
  pointer-events: none;
}
.gallery02 .swiper-fade .swiper-slide-active {
  pointer-events: auto;
}
.gallery02 .swiper-controller {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  padding-top: 62.5%;
}
.gallery02 .swiper-button-prev,
.gallery02 .swiper-button-next {
  position: absolute;
  z-index: 1;
  top: 0;
  bottom: 0;
  margin: auto;
}
.gallery02 .swiper-button-prev {
  right: calc(100% + 3.2rem);
}
.gallery02 .swiper-button-next {
  left: calc(100% + 3.2rem);
}
.gallery02 .slide {
  display: block;
  overflow: hidden;
}
.gallery02 .slide-media {
  display: block;
  padding-top: 62.5%;
  border-radius: 4px;
}
.gallery02 .slide-media img {
  -o-object-fit: contain;
  object-fit: contain;
}
.gallery02 .slide-title {
  font-weight: bold;
  line-height: 1.6;
  padding: 3.2rem 0;
}
.gallery02 .thumb-wrapper {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 8px;
}
.gallery02 .thumb-media {
  padding-top: 100%;
  cursor: pointer;
  -webkit-transition: var(--transition);
  transition: var(--transition);
  border-radius: 4px;
}
.gallery02 .thumb-media img {
  -webkit-transition: var(--transition);
  transition: var(--transition);
}
.gallery02 .thumb-media-active {
  -webkit-transform: scale(0.9);
  transform: scale(0.9);
  opacity: 0.3;
}
.gallery02 .thumb-media-active img {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

@media only screen and (max-width: 1024px) {
  html {
    -webkit-text-size-adjust: 100%;
  }
  .l-inner {
    padding: 0 4rem;
  }
  .pc {
    display: none !important;
  }
  .gallery02 .swiper-button-prev::before,
  .gallery02 .swiper-button-next::before {
    background-color: rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: var(--box-shadow-dark);
    box-shadow: var(--box-shadow-dark);
  }
  .gallery02 .swiper-button-prev::after,
  .gallery02 .swiper-button-next::after {
    border-color: #fff;
  }
  .gallery02 .swiper-button-prev {
    right: calc(100% - 3.2rem);
  }
  .gallery02 .swiper-button-next {
    left: calc(100% - 3.2rem);
  }
}

@media only screen and (max-width: 599px) {
  html {
    font-size: 50%;
  }
  .pc-tab {
    display: none !important;
  }
  .gallery02 .thumb-wrapper {
    grid-template-columns: repeat(5, 1fr);
  }
}

@media only screen and (min-width: 1025px) {
  .tab-sp {
    display: none !important;
  }
  .swiper-button-prev::before,
  .swiper-button-next::before {
    -webkit-transition: var(--transition);
    transition: var(--transition);
  }
  .swiper-button-prev:hover::before,
  .swiper-button-next:hover::before {
    -webkit-transform: scale(1.2);
    transform: scale(1.2);
  }
  .gallery02 .thumb-media:hover {
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
  }
  .gallery02 .thumb-media:hover img {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }
}

@media only screen and (min-width: 600px) {
  .sp {
    display: none !important;
  }
}
</style>
