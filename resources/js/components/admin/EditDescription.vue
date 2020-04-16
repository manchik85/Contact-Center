<template>
  <div class="container div_c">

    <input type="hidden" name="user_id" v-bind:value="data.id">

    <div class="form-group">
      <div class="input-group" id="description">
        <textarea class="form-control pad_10" rows="3" id="users_deck" name="users_deck" placeholder="Комментарий">{{ data.deck }}</textarea>
      </div>
    </div>

    <button id="deck-batton" class="btn btn-primary" @click="admChengeDeck">
      <i class="fal fa-pencil-alt"></i> &nbsp;Сохранить изменения&nbsp;
    </button>
    <div class="div px_10 float_n clb"></div>

    <div class="clb"></div>
  </div>

</template>

<script>
  import axios from 'axios';
  import $ from 'jquery';

  export default {

    props: [
      'data'
    ],

    data: function () {
      return {}
    },
    methods: {

      admChengeDeck: function () {
        axios.post('/chenge_self_descr', {user_id: this.data.id, users_deck: $('#users_deck').val()})
          .then(() => {
            $('#deck-batton').addClass('btn-success').removeClass('btn-primary').html('<i class="fal fa-check"></i> &nbsp;Изменения сохранены');
          });
      }
    }

  }
</script>
