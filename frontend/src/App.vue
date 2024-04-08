<script setup>
    import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
    <header>
        <div class="wrapper">

        <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" @click="$router.push('/')">Início</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="navbar-nav">
                        <a class="nav-link" @click="$router.push('/produto/tipo')">Tipo Produto</a>
                        <a class="nav-link" @click="$router.push('/produto')">Produtos</a>
                        <a class="nav-link" @click="$router.push('/venda')">Venda</a>
                    </div>
                </div>
            </div>
        </nav>

        </div>
    </header>

    <RouterView id="body" />
</template>

<style scoped>

    #body {
        margin: 60px 30px 60px 30px;
    }

    header {
        width: 100vw;
        top: 0px;
    }

    .logo {
        display: block;
        margin: 0 auto 2rem;
    }

    nav {
        font-size: 12px;
        text-align: center;
    }

    nav a.router-link-exact-active {
        color: var(--color-text);
    }

    nav a.router-link-exact-active:hover {
        background-color: transparent;
    }

    nav a {
        cursor: pointer;
        display: inline-block;
        padding: 0 1rem;
        border-left: 1px solid var(--color-border);
    }

    nav a:first-of-type {
        border: 0;
    }

</style>