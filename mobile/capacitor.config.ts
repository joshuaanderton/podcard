import { CapacitorConfig } from '@capacitor/cli'

const config: CapacitorConfig = {
  appId: 'com.stafford.vite.capacitor',
  appName: 'vite-and-capacitor',
  webDir: 'dist',
  bundledWebRuntime: false,
  server: {
    url: 'http://127.0.0.1:5173'
  },
  plugins: {
    CapacitorHttp: {
      enabled: true
    },
    CapacitorCookies: {
      enabled: true,
    },
    CapacitorPreferences: {
      enabled: true
    }
  },
};

export default config;
