<template>
  <div class="wrapper">
    <Header/>
    <div class="content">
      <h2>Ajouter un morceau</h2>
      <UploadForm/>
    </div>
  </div>
</template>

<script>
import Header from '@/components/content/Header'
import UploadForm from "@/components/upload/UploadForm";

export default {
  name: "Upload",
  components: {
    Header,
    UploadForm
  }
}
</script>

<style lang="scss" scoped>
.content {
  margin-top: 5rem;
  padding: 0 7rem;

  h2 {
    margin-left: 2rem;
  }
}
</style>
