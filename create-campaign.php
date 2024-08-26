<?php /* Template Name: Create Campaign */


//add vuejs
wp_enqueue_script( 'vuejs', 'https://cdnjs.cloudflare.com/ajax/libs/vue/3.4.38/vue.global.prod.min.js', [], null, true );
//add tailwindcss
wp_enqueue_style( 'tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css', [], null, 'all' );

get_header();



?>
<style>
    html {
        font-size: 1rem;
    }
    .container {
        max-width:100%;
        width: 100%;
    }
    /* Tooltip container */
    .tooltip {
        position: relative;
        display: inline-block;
    }

    /* Tooltip text */
    .tooltip .tooltiptext {
        visibility: hidden;
        width: 300px;
        background-color: white;
        color: black;
        padding: 1rem;
        border-radius: 6px;

        /* Position the tooltip text - see examples below! */
        position: absolute;
        z-index: 1;
        font-size: 1rem;
        font-weight: 400;
        border: 1px solid #e2e8f0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Show the tooltip text when you mouse over the tooltip container */
    .tooltip:hover .tooltiptext {
        visibility: visible;
    }
    .tooltip:focus .tooltiptext {
        visibility: visible;
    }

    .tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 50%;
        right: 100%;
        margin-top: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent black transparent transparent;
    }
    .help-text {
        font-size: 1rem;
        color: #4a5568;
        font-weight: 400;
    }
    .valid-input {
        border: 1px solid #68d391;
    }
    .valid-input:focus {
        border: 1px solid #68d391;
    }
    .invalid-input {
        border: 1px solid #f56565;
    }

    form span.error {
        display: none;
        font-size: 0.8em;
        position: absolute;
        color: red;
    }

    form input[type='text']:invalid:not(:focus):not(:placeholder-shown) ~ .error {
        display: block;
    }
    form input[type='email']:invalid:not(:focus):not(:placeholder-shown) ~ .error {
        display: block;
    }
    form input[type='text']:invalid:not(:focus):not(:placeholder-shown) {
        border: 1px solid #f56565;
    }
    form input[type='email']:invalid:not(:focus):not(:placeholder-shown) {
        border: 1px solid #f56565;
    }
    form input[type='text']:valid:not(:focus):not(:placeholder-shown) {
        border: 1px solid #68d391;
    }
    form input[type='email']:valid:not(:focus):not(:placeholder-shown) {
        border: 1px solid #68d391;
    }

    label {
        display: block;
        margin-bottom: 1.5rem;
    }

    input {
        width: 100%;
        padding: 0.5rem;
        border-radius: 0.25rem;
        line-height: 1.5rem;
        border-color: #d1d5db;
        box-sizing: border-box;
        border-width: 0;
        border-bottom-width: 1px;
        border-style: solid;
        outline: 2px solid transparent;
        outline-offset: 2px;
    }
    input:focus {
        border-color: #2563eb;
    }
</style>
<div id="app" class="w-auto bg-gray-50 max-w-6xl mx-auto p-7 px-12 rounded-md shadow-md text-lg">
    <div class="text-center text-3xl">
        <div>
            <h1>Create Campaign</h1>
        </div>
    </div>
    <form>
        <h3 class="text-3xl font-bold my-7">
            Account Details
        </h3>
        <label class="font-bold text-gray-700 tracking-wide">Email
            <input v-model="email" class="" type="email" placeholder="mail@gmail.com" required>
            <span class="error">Please enter a valid email address</span>
        </label>
        <label class="font-bold text-gray-700 tracking-wide">
            <span class="flex items-center gap-x-1 pb-2">
                Name (optional)
                <div class="trigger-help-text" @click="show_help('name')">
                    <img class="dt-icon w-4 h-4 inline-block" src="<?php echo esc_html( get_template_directory_uri() . '/assets/images/help.svg' ) ?>"/>
                </div>
            </span>
            <span class="help-text" v-show="showhelp.name">
                Your name is optional but helps us to personalize your experience.
                <br>Your answer is kept private.
            </span>
            <input @blur="(e)=>handle_blur(e)" v-model="name" :class="{'valid-input': name?.length > 4}" type="text" placeholder="Pray4France">
        </label>
        <div class="relative pb-7">
            <label class="font-bold text-gray-700 tracking-wide">
                <span class="flex items-center gap-x-1 pb-2">
                    Do you have an existing prayer network? If so, what is the link? (optional)
                    <span class="tooltip">
                        <img class="dt-icon w-4 h-4 inline-block" src="<?php echo esc_html( get_template_directory_uri() . '/assets/images/help.svg' ) ?>"/>
                    </span>
                </span>
                <input @blur="(e)=>handle_blur(e)" type="text" placeholder="Pray4France">
            </label>
        </div>


        <h3 class="text-3xl font-bold my-7">
            Campaign Details
        </h3>
        <div class="relative pb-7">
            <label class="font-bold text-gray-700 tracking-wide">
                <span class="flex items-center gap-x-1 pb-2">
                    Campaign Name
                    <span class="tooltip">
                        <img class="dt-icon w-4 h-4 inline-block" src="<?php echo esc_html( get_template_directory_uri() . '/assets/images/help.svg' ) ?>"/>
                        <span class="tooltiptext">
                            The campaign name describes your prayer focus.
                            <br>Examples Pray4France, Pray 4 the French, Ramadan Prayer, etc.
                        </span>
                    </span>
                </span>
                <input @blur="(e)=>handle_blur(e, 4)" type="text" placeholder="Pray4France" required pattern=".{4,}">
                <span class="error">Campaign name must be at least 4 characters</span>
            </label>
        </div>
        <!--Subdomain-->
        <div class="relative pb-7">
            <label class="font-bold text-gray-700 tracking-wide">
                <span class="flex items-center gap-x-1 pb-2">
                    Campaign Url
                    <span class="tooltip">
                        <img class="dt-icon w-4 h-4 inline-block" src="<?php echo esc_html( get_template_directory_uri() . '/assets/images/help.svg' ) ?>"/>
                        <span class="tooltiptext">
                            This is the url you will share to your prayer network.
                            <br>We recommend domains like pray4france, pray4france-ramadan, france-ramadan, france-lent, france247, etc.
                            <br>Want your own custom domain? <a href="https://prayer.tools/docs/hosting-options/">Find out more</a>
                        </span>
                    </span>
                </span>
                <input @blur="(e)=>handle_blur(e, 4)" style="width: auto;" type="text" placeholder="pray4france" required pattern="^[\d\w\-_]{4,}$">.prayer.tools
                <span class="error">Subdomain must be at least 4 characters and cannot contain spaces</span>
            </label>
        </div>
        <div class="flex gap-20">
            <!--Start Date-->
            <div class="relative pb-7">
                <label class="font-bold text-gray-700 tracking-wide">Campaign Start Date<br>
                    <input type="date" required pattern="\d{4}-\d{2}-\d{2}" placeholder="yyyy-mm-dd">
                </label>
            </div>
            <!--End Date-->
            <div class="relative pb-7">
                <label class="font-bold text-gray-700 tracking-wide">Campaign End Date (optional)<br>
                    <input type="date">
                </label>
            </div>
        </div>

        <button style="background-color: var(--primary-color);" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Create Campaign</button>

    </form>
</div>


<?php
get_footer();
?>

<script>
  const { createApp, ref } = Vue
  // import Tooltip from 'primevue/tooltip';

  const app = createApp({
    setup() {
      const message = ref('Hello vue!')
      const showhelp = ref({})
      const valid_fields = ref({})
      return {
        message,
        email: ref(''),
        name: ref(''),
        network: ref(''),
        campaign_name: ref(''),
        campaign_url: ref(''),
        start_date: ref(''),
        end_date: ref(''),
        showhelp
      }
    },
    computed: {
      valid_email() {
        //match regex email
        return this.email.match(/^\S+@\S+\.\S+$/)
      }
    },
    methods: {
      show_help(key, state = null) {
        this.showhelp[key] = state!==null ? state:!this.showhelp[key]
      },
      handle_blur(e, min_length = null) {
        if (min_length===null) {
          e.target.classList.remove('invalid-input')
          e.target.classList.toggle('valid-input', e.target.value.length)
        } else {
          if (e.target.value.length >= min_length) {
            e.target.classList.remove('invalid-input')
            e.target.classList.add('valid-input')
          } else {
            e.target.classList.remove('valid-input')
            e.target.classList.add('invalid-input')
          }
        }
      }
    }
  })
  app.mount('#app')
</script>
