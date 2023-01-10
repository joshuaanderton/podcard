import './bootstrap'

import alpine from 'alpinejs'
import Vue from 'vue/dist/vue.esm'
import 'clipboard'
import Choices from 'choices.js'

import '../css/app.css'

window.Choices = Choices

if (document.getElementById('player_builder')) {

  new Vue({
    el: "#player_builder",
    props: {
      feed_url: {
        type: String,
        default: ''
      },
      episode: {
        type: String,
        default: ''
      },
      color: {
        type: String,
        default: ''
      },
    },
    data: () => ({
      player_url: window.location.href.indexOf('.test') > 0 ? 'http://player.podcard.test' : 'https://player.podcard.co',
      demos: [
        {feed_url: 'https://feeds.podhunt.app/feeds/daily/rss',   color: '#8772c7', episode: '37signals'},
        {feed_url: 'https://feeds.transistor.fm/founderquest',    color: '#b8702d', episode: 'We\'re Going on Summer Vacation!'},
        {feed_url: 'https://feeds.transistor.fm/build-your-saas', color: '#fbc85c', episode: '70'},
        {feed_url: 'https://feeds.transistor.fm/ramen',           color: '#ff4500', episode: 'tuple.app'},
      ]
    }),
    created() {
      var random_demo = Math.floor((Math.random() * this.demos.length) + 1) - 1;
      this.feed_url = this.demos[random_demo].feed_url;
      this.episode = this.demos[random_demo].episode;
      this.color = this.demos[random_demo].color;
    },
    mounted() {
      //
    }
  })

} else {

  window.Alpine = alpine
  Alpine.start()
}

const animateElements = document.getElementsByClassName('fade-up')

if (animateElements && animateElements.length > 0) {
  setTimeout(() => (
    Array.from(animateElements).map(el => {
      el.classList.add('active')
    })
  ), 100)
}