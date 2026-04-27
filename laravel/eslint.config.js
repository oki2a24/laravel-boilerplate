import globals from "globals";
import pluginJs from "@eslint/js";
import pluginVue from "eslint-plugin-vue";
import eslintConfigPrettier from "eslint-config-prettier";
import vueParser from "vue-eslint-parser";

export default [
  {
    files: ["**/*.{js,mjs,cjs,vue}"],
    languageOptions: {
      parser: vueParser,
      parserOptions: {
        ecmaVersion: "latest",
        sourceType: "module",
      },
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
  },
  pluginJs.configs.recommended,
  ...pluginVue.configs["flat/recommended"],
  eslintConfigPrettier,
  {
    rules: {
      "vue/multi-word-component-names": "off",
    },
  },
  {
    ignores: [
      "**/node_modules/**",
      "**/dist/**",
      "public/build/**",
      "public/hot/**",
      "public/storage/**",
      "vendor/**",
      ".env",
      ".env.backup",
      ".phpunit.result.cache",
      "Homestead.json",
      "Homestead.yaml",
      "auth.json",
      "npm-debug.log",
      "yarn-error.log",
      ".idea/**",
      ".vscode/**",
      ".php-cs-fixer.cache",
    ],
  },
];