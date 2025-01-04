import { Cloudinary } from '@cloudinary/url-gen'
import type { App } from 'vue'

export default {
  install: (app: App) => {
    app.use(Cloudinary, {
      configuration: {
        cloudName: 'chientt-e-commerce',
        uploadPreset: 'bm8q8u7p',
      },
    })
  },
}
