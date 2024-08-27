<?php /* Template Name: Create Campaign */


//add vuejs
wp_enqueue_script( 'vuejs', 'https://cdnjs.cloudflare.com/ajax/libs/vue/3.4.38/vue.global.prod.min.js', [], null, true );
//add tailwindcss
wp_enqueue_style( 'tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css', [], null, 'all' );

get_header();

$languages = [
    [ 'lang' => 'en_US', 'value' => 'English' ],
    [ 'lang' => 'it_IT', 'value' => 'Italian' ],
    [ 'lang' => 'es_ES', 'value' => 'Spanish' ],
    [ 'lang' => 'pt_BR', 'value' => 'Portuguese' ],
    [ 'lang' => 'fr_FR', 'value' => 'French' ],
    [ 'lang' => 'ar', 'value' => 'Arabic' ],
    [ 'lang' => 'te', 'value' => 'Telugu' ],
    [ 'lang' => 'hi_IN', 'value' => 'Hindi' ],
    [ 'lang' => 'kn', 'value' => 'Kannada' ],
    [ 'lang' => 'ta', 'value' => 'Tamil' ],
    [ 'lang' => 'ur', 'value' => 'Urdu' ],
    [ 'lang' => 'zh_Hans', 'value' => 'Chinese' ],
]



?>
<style>
    html {
        font-size: 1rem;
    }
    .container {
        max-width:100%;
        width: 100%;
        margin:0
    }
    .help-text {
        font-size: 1rem;
        color: #4a5568;
        font-weight: 400;
        margin-bottom: 0.5rem;
    }

    form span.error {
        display: none;
        font-size: 0.8em;
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
        margin-bottom: 2rem;
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
    input[type='checkbox'], input[type='radio'] {
        width: 1rem;
        height: 1rem;
        margin-right: 1rem;
    }
</style>
<div id="app" class="w-auto bg-gray-50 max-w-5xl m-1 mb-20 md:mx-auto p-3 md:p-7 rounded-md shadow-md text-lg">
    <div v-if="view==='create'">
        <div class="text-center text-2xl md:text-3xl">
            <div>
                <h1>Create Campaign</h1>
            </div>
        </div>
        <form ref="form" @submit.prevent="submit_form">
            <h3 class="text-3xl font-bold my-7">
                Account Details
            </h3>
            <label class="font-bold text-gray-700 tracking-wide">Email<span style="color:red"> *</span>
                <input v-model="email" class="" type="email" placeholder="email@gmail.com" required>
                <span class="error">Please enter a valid email address</span>
            </label>
            <label class="font-bold text-gray-700 tracking-wide">
                    Name <span class="text-gray-400 text-sm">Answer is kept private</span>
                <!--<span class="flex items-baseline gap-x-1 pb-2">-->
                <!--    <div class="trigger-help-text" @click="show_help('name')">-->
                <!--        <img class="dt-icon w-4 h-4 inline-block" src="--><?php //echo esc_html( get_template_directory_uri() . '/assets/images/help.svg' ) ?><!--"/>-->
                <!--    </div>-->
                <!--</span>-->
                <input @blur="(e)=>handle_blur(e)" v-model="name" :class="{'valid-input': name?.length > 4}" type="text" placeholder="Ahmed">
                <!--<div class="help-text" v-show="showhelp.name">-->
                <!--    Your name is optional but helps us to personalize your experience.-->
                <!--    <br>Your answer is kept private.-->
                <!--</div>-->
            </label>
            <label class="font-bold text-gray-700 tracking-wide">
                    Do you have an existing prayer network? If so, what is the link? <span class="text-gray-400 text-sm">Answer is kept private</span>
                <!--<span class="flex items-baseline gap-x-1 pb-2">-->
                <!--    <span class="trigger-help-text" @click="show_help('network')">-->
                <!--        <img class="dt-icon w-4 h-4 inline-block" src="--><?php //echo esc_html( get_template_directory_uri() . '/assets/images/help.svg' ) ?><!--"/>-->
                <!--    </span>-->
                <!--</span>-->
                <!--<div class="help-text" v-show="showhelp.network">-->
                <!--    Your answer is kept private.-->
                <!--</div>-->
                <input v-model="network" @blur="(e)=>handle_blur(e)" type="text" placeholder="https://network.com">
            </label>
            <label class="font-bold text-gray-700 tracking-wide">
                    What is your target location or people group?  <span class="text-gray-400 text-sm">Answer is kept private</span>
                <!--<span class="flex items-baseline gap-x-1 pb-2">-->
                <!--    <span class="trigger-help-text" @click="show_help('location')">-->
                <!--        <img class="dt-icon w-4 h-4 inline-block" src="--><?php //echo esc_html( get_template_directory_uri() . '/assets/images/help.svg' ) ?><!--"/>-->
                <!--    </span>-->
                <!--</span>-->
                <!--<span class="help-text" v-show="showhelp.location">-->
                <!--    Your answer is kept private.-->
                <!--</span>-->
                <input v-model="location" @blur="(e)=>handle_blur(e)" type="text" placeholder="The French">
            </label>
            <label class="text-gray-700 tracking-wide">
                <input v-model="newsletter" @blur="(e)=>handle_blur(e)" type="checkbox" checked style="width: 1rem; height: 1rem; margin-right: 1rem;">Sign up for Prayer.Tools news and opportunities, and occasional communication from GospelAmbition.org
            </label>


            <h3 class="text-3xl font-bold my-7">
                Campaign Details
            </h3>
            <label class="font-bold text-gray-700 tracking-wide pb-7">
                <span class="flex items-center gap-x-1 pb-2">
                    Campaign Name<span style="color:red"> *</span>
                    <!--<span class="tooltip">-->
                    <!--    <img class="dt-icon w-4 h-4 inline-block" src="--><?php //echo esc_html( get_template_directory_uri() . '/assets/images/help.svg' ) ?><!--"/>-->
                    <!--</span>-->
                </span>
                <span class="help-text" style="display: block">
                    The campaign name describes your prayer focus. Examples: Pray4France, Pray 4 the French, Ramadan Prayer, etc.
                </span>
                <input v-model="campaign_name" @blur="(e)=>handle_blur(e, 4)" type="text" placeholder="Pray4France" required pattern=".{4,}">
                <span class="error">Campaign name must be at least 4 characters</span>
            </label>
            <!--Subdomain-->
            <div class="relative pb-7">
                <label class="font-bold text-gray-700 tracking-wide">
                    <span class="flex items-center gap-x-1 pb-2">
                        Campaign Url<span style="color:red"> *</span>
                        <!--<span class="tooltip">-->
                        <!--    <img class="dt-icon w-4 h-4 inline-block" src="--><?php //echo esc_html( get_template_directory_uri() . '/assets/images/help.svg' ) ?><!--"/>-->
                        <!--</span>-->
                    </span>
                    <span class="help-text" style="display: block">
                        This is the url you will share to your prayer network. We recommend domains like pray4france, pray4france-ramadan, france-ramadan, france-lent, france247, etc. Use a custom domain with <a href="https://prayer.tools/docs/hosting-options/">hosting options.</a>
                    </span>
                    <input v-model="campaign_url" @blur="(e)=>handle_blur(e, 4)" style="width: auto;" type="text" placeholder="pray4france" required pattern="^[\d\w\-_]{4,}$">.prayer.tools
                    <span class="error">Subdomain must be at least 4 characters and cannot contain spaces</span>
                </label>
            </div>
            <div class="help-text" style="color:red" v-if="ramadan">
                Please make sure that the start and end dates match when Ramadan will start and end in your target region(s). Your country might have different dates.
            </div>
            <div class="flex gap-20">
                <!--Start Date-->
                <div class="relative pb-7">
                    <label class="font-bold text-gray-700 tracking-wide">Campaign Start Date<span style="color:red"> *</span><br>
                        <input v-model="start_date" type="date" required pattern="\d{4}-\d{2}-\d{2}"
                               placeholder="yyyy-mm-dd" min="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
                    </label>
                </div>
                <!--End Date-->
                <div class="relative pb-7">
                    <label class="font-bold text-gray-700 tracking-wide">Campaign End Date (optional)<br>
                        <input v-model="end_date" type="date" min="<?php echo esc_html( gmdate( 'Y-m-d' ) ); ?>">
                    </label>
                </div>
            </div>

            <label class="text-gray-700 tracking-wide">
                <input v-model="pt_agreement" @blur="(e)=>handle_blur(e)" type="checkbox" required style="width: 1rem; height: 1rem; margin-right: 1rem;">I agree to use this prayer campaign tool in accordance with the <a href="https://prayer.tools/about/" target="_blank">vision and intent</a> of Prayer.Tools to mobilize extraordinary prayer for a specific people or place<span style="color:red"> *</span><br>
            </label>
            <label class="text-gray-700 tracking-wide">
                <input v-model="pt_listing" @blur="(e)=>handle_blur(e)" type="checkbox" required style="width: 1rem; height: 1rem; margin-right: 1rem;">I agree that my prayer campaign can be listed on Prayer.Tools<span style="color:red"> *</span><br>
            </label>

            <div v-if="ramadan">
                <h3 class="text-3xl font-bold my-7">
                    Ramadan
                </h3>
                <h4 class="text-lg font-bold">Prayer Fuel</h4>
                <p>
                    Each campaign needs prayer fuel for the users to use to pray every day. Ramadan prayer fuel is provided in 11 languages. We intend for you to improve and customize this prayer fuel, but it can be used as it is. Let us know which option you plan on choosing:
                </p>
                <div class="my-4">
                    <label class="m-0">
                        <input type="radio" v-model="prayer_fuel" name="prayer_fuel" value="starter" required>I will use the default prayer fuel
                    </label>
                    <label class="m-0">
                        <input type="radio" v-model="prayer_fuel" name="prayer_fuel" value="customize">I will use the default prayer fuel and customize it
                    </label>
                    <label class="m-0">
                        <input type="radio" v-model="prayer_fuel" name="prayer_fuel" value="custom">I plan on creating my own prayer fuel
                    </label>
                </div>

                <h4 class="text-lg font-bold">Languages</h4>
                <p>
                    Select which of the installed languages you would like to use for your campaign. You can select multiple languages.
                </p>
                <div class="my-4">
                    <label class="m-0" v-for="language in languages" :key="language.code">
                        <input type="checkbox" v-model="language.selected" name="prayer_fuel" :value="language.code" :id="language.code">{{language.value}}
                    </label>
                </div>
            </div>

            <div>
                <span class="error" style="display: block">{{submit_error}}</span>
            </div>
            <button
                style="background-color: var(--primary-color);"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Create
                Campaign
            </button>

        </form>
    </div>
    <div v-if="view === 'success'">
        <div class="text-center text-2xl md:text-3xl">
            <div>
                <h1>Success</h1>
            </div>
        </div>
        <div class="text-center">
            <p class="text-lg">
                Your campaign has been created. You will receive an email with instructions on how to access your campaign.
            </p>
        </div>
    </div>
</div>


<?php
get_footer();
?>

<script>
  const js_data = <?php echo json_encode( [ //phpcs:ignore
      'rest' => rest_url(),
      'nonce' => wp_create_nonce( 'wp_rest' ),
      'ramadan_start' => dt_get_next_ramadan_start_date(),
      'ramadan_end' => dt_get_next_ramadan_end_date(),
      'languages' => $languages
  ] ); ?>;

  const query_params = new URLSearchParams(window.location.search)
  let ramadan = query_params.get('ramadan') !== null

  const { createApp, ref } = Vue

  const app = createApp({
    setup() {
      const message = ref('Hello vue!')
      const showhelp = ref({})
      const view = ref('create')
      let start_date = ref('')
      let end_date = ref('')
      if ( ramadan ){
        start_date = js_data.ramadan_start
        end_date = js_data.ramadan_end
      }
      return {
        message,
        email: ref(''),
        name: ref(''),
        network: ref(''),
        campaign_name: ref(''),
        campaign_url: ref(''),
        start_date,
        end_date,
        newsletter: ref(true),
        location: ref(''),
        pt_agreement: ref(false),
        pt_listing: ref(false),
        showhelp,
        submit_error: ref(''),
        view,
        ramadan: ref(ramadan),
        prayer_fuel: ref(''),
        languages: ref(js_data.languages),
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
      },
    async submit_form() {
      if ( this.end_date && new Date( this.end_date ) < new Date( this.start_date ) ) {
        this.submit_error = 'Campaign end date must be after the campaign start date'
        return
      }
      const languages = this.languages.map(l=>l.selected ? l.lang : null).filter(l=>l!==null);
      if ( this.ramadan && languages.length === 0 ) {
        this.submit_error = 'Please select at least one language for your campaign and prayer fuel'
        return
      }

      let data = {
        email: this.email,
        name: this.name,
        network: this.network,
        campaign_name: this.campaign_name,
        campaign_url: this.campaign_url,
        start_date: this.start_date,
        end_date: this.end_date,
        nonce: js_data.nonce,
        newsletter: this.newsletter,
        location: this.location,
        pt_agreement: this.pt_agreement,
        pt_listing: this.pt_listing,
        prayer_fuel: this.prayer_fuel,
        languages
      }
      fetch(js_data.rest + 'dt-campaigns/v1/create_campaign', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      }).then(status => {
        if ( !status.ok ){
          return Promise.reject(status)
        }
        this.view = 'success'
      })
      .catch((error) => {
        error.json().then(r=>{
            this.submit_error = r.message;
        })
        console.error('Error:', error)
      })
    }
    }
  })
  app.mount('#app')
</script>
