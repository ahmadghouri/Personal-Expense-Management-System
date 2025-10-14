import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from "@tailwindcss/vite";
// https://vite.dev/config/
export default defineConfig({
    css: ['./src/style.css'],
  plugins: [vue(),tailwindcss()],
  
})
