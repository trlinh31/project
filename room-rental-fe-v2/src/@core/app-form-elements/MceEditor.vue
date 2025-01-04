<script setup lang="ts">
import tinymce from 'tinymce/tinymce'

import TinymceEditor from '@tinymce/tinymce-vue'
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/quickbars'
import 'tinymce/themes/silver/theme'
import 'tinymce/icons/default/icons'
import 'tinymce/models/dom'
import 'tinymce/plugins/autolink'
import 'tinymce/plugins/autoresize'
import 'tinymce/plugins/fullscreen'
import 'tinymce/plugins/image'
import 'tinymce/plugins/insertdatetime'
import 'tinymce/plugins/link'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/media'
import 'tinymce/plugins/preview'
import 'tinymce/plugins/table'
import 'tinymce/plugins/wordcount'
import 'tinymce/plugins/code'
import 'tinymce/plugins/searchreplace'

const props = defineProps<{
  modelValue: string
  setting?: object
  disabled?: boolean
}>()

const emits = defineEmits(['update:modelValue'])

defineOptions({
  name: 'MceEditor',
})

const content = ref('')

//
onMounted(() => {
  tinymce.init({})
  content.value = props.modelValue
})

watch(
  () => content.value,
  (newVal: string) => {
    emits('update:modelValue', newVal)
  },
)

const defaultSetting = ref({
  license_key: 'gpl',
  selector: 'textarea',
  min_height: 250,
  max_height: 700,
  placeholder: 'Write something...',
  plugins: 'autolink autoresize fullscreen image link lists media preview table wordcount code searchreplace',
  toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor removeformat | link image media table insertdatetime searchreplace | preview code fullscreen | fontsizeselect fontselect',
  branding: false,
  promotion: false,
  menubar: true,
  toolbar_mode: 'sliding',
  fontsize_formats: '12px 14px 16px 18px 24px 36px 48px 56px 72px',

  // https://www.tiny.cloud/docs/tinymce/6/file-image-upload/#images_upload_handler
  images_upload_handler: (blobInfo: any) => new Promise(resolve => {
    const img = `data:image/jpeg;base64,${blobInfo.base64()}`

    resolve(img)
  }),
  file_picker_types: 'media',
  file_picker_callback: (callback: any, value: any, meta: any) => {
    if (meta.filetype === 'media') {
      // ,,m4v,avi,wmv,rmvb,mov,mpg,mpeg,webm
      const filetype = '.mp4,.mkv, .avi,.wmv, .rmvb,.mov,.mpg,.mpeg,.webm' // 限制文件的上传类型
      const inputElem = document.createElement('input') as any

      inputElem.setAttribute('type', 'file')
      inputElem.setAttribute('accept', filetype)
      inputElem.click()
      inputElem.onchange = async function () {
        // const file = inputElem.files[0] // 为 HTMLInputElement 构造函数中的 this，指向 input 实例对象
        // const isValid = await validateVideo(file)
        // if (isValid) {
        //   await uploadVideoFile(file, callback)
        // }
        // else {
        //   callback()
        // }
      }
    }
  },
  video_template_callback: (data: any) => {
    return `<video width="100%" height="auto" controls="controls" src=${data.source} />`
  },
})

const completeSetting = computed(() => {
  return Object.assign(defaultSetting.value, props.setting)
})
</script>

<template>
  <TinymceEditor v-model="content" :init="completeSetting" license-key="gpl" api-key="yt2rl2ly7xbg83giohyz28tryp3fsrybshvh74i4l3rd7dd2" />
</template>

<style lang="scss" scoped>
.preview {
  margin-top: 10px;

  &::before {
    display: block;
    content: "Preview：";
  }
}
</style>
