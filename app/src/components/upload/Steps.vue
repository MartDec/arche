<template>
  <div id="steps">
    <ol>
      <li @click="changeStep" data-step="1" :class="firstStep"></li>
      <li @click="changeStep" data-step="2" :class="secondStep"></li>
      <li @click="changeStep" data-step="3" :class="thirdStep"></li>
    </ol>
  </div>
</template>

<script>
export default {
  name: 'Steps',
  props: [
    'currentStep',
    'artistId',
    'albumId'
  ],
  methods: {
    changeStep: function (event) {
      const targetStep = parseInt(event.target.getAttribute('data-step'))
      this.$emit('changestep', targetStep)
    }
  },
  computed: {
    firstStep: function () {
      if (this.currentStep === 1)
        return 'selected'

      return null
    },
    secondStep: function () {
      if (this.currentStep === 2)
        return 'selected'

      return null
    },
    thirdStep: function () {
      if (this.currentStep === 3)
        return 'selected'

      return null
    }
  }
}
</script>

<style lang="scss">
#steps {
  width: 100%;

  ol {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(5em, 1fr));
    grid-template-rows: auto;
    grid-gap: 1em;
    width: 100%;
    margin: 0;
    padding: 0;
    margin-bottom: 3rem;

    counter-reset: item;
    list-style-type: none;

    li {
      --selected: var(--red);

      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-size: .875em;
      color: var(--selected);
      text-align: center;
      transition: all .3s ease;

      &::before {
        position: relative;
        z-index: 1;
        content: counters(item, ".") " ";
        counter-increment: item;

        background: var(--selected);
        display: flex;
        justify-content: center;
        align-items: center;
        width: 2em;
        height: 2em;
        border-radius: 50%;
        color: white;
        margin-bottom: .75em;
        cursor: pointer;
      }

      &::after {
        content: '';
        position: absolute;
        top: 1em;
        left: 50%;
        z-index: 0;

        display: block;
        width: calc(100% + 1em);
        height: 2px;
        background: linear-gradient(to right, var(--selected) 25%, rgba(255, 255, 255, 0) 0%) repeat-x;
        background-size: 4px 1px;
      }

      &:last-of-type {
        &::after {
          display: none;
        }
      }

      &.selected {
        --selected: var(--black);
      }
    }
  }
}
</style>
