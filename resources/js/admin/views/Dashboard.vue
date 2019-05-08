<template>
    <div>
        <section class="hero is-info welcome is-small has-margin-bottom-sm">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title is-1">
                        Hello, ???
                    </h1>
                    <h2 class="subtitle">
                        I hope you are having a great day!
                    </h2>
                </div>
            </div>
        </section>
        <section class="info-tiles">
            <div class="tile is-ancestor has-text-centered">
                <a class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">0</p>
                        <p class="subtitle">Users</p>
                    </article>
                </a>
                <a class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">0</p>
                        <p class="subtitle">Albums</p>
                    </article>
                </a>
                <a class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">0</p>
                        <p class="subtitle">Cosplayers</p>
                    </article>
                </a>
                <a class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">0</p>
                        <p class="subtitle">Contacts</p>
                    </article>
                </a>
            </div>
        </section>
        <div class="columns">
            <div class="column is-6">
                <!--                @include('admin.partials._activities_card', ['activities' => $activities])-->
            </div>
            <div class="column is-6"></div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from 'vue';
import Component from 'vue-class-component';
@Component
export default class Home extends Vue {}
</script>
