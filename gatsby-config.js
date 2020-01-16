/**
 * Configure your Gatsby site with this file.
 *
 * See: https://www.gatsbyjs.org/docs/gatsby-config/
 */

// Loads .env.development in order to enable Playground mode of Graphql
require('dotenv').config({
  path: `.env.${process.env.NODE_ENV}`,
});

module.exports = {
  siteMetadata: {
    title: 'Adriano Rosa',
    description: `Analista Programador Backend e Frontend - Web Developer FullStack`,
    author: {
      name: 'Adriano Rosa',
      description: 'Web Developer',
      url: 'https://adrianorosa.com',
      email: 'info@adrianorosa.com',
      twitter: 'https://twitter.com/adrianorosa',
      github: 'https://github.com/adrianorsouza',
      avatar:
        'https://secure.gravatar.com/avatar/ad6facb479066db3b8007443ce79fcdd?s=100',
      //avatar: "http://127.0.0.1:4000/images/ad6facb479066db3b8007443ce79fcdd.jpg	",
    },
    baseurl: '',
    blog_url: '/blog',
    url: 'https://adrianorosa.com',
    locale: 'pt-br',
    disqus_enable: (process.env.NODE_ENV === 'production'),
    disqus_shortname: 'adrianorosa',
    ga_tracker_id: 'UA-5189486-5',
  },
  // assetPrefix: `https://cdn.example.com`,
  plugins: [
    `gatsby-plugin-react-helmet`,
    `gatsby-plugin-sass`,
    {
      resolve: `gatsby-source-filesystem`,
      options: {
        name: `posts`,
        path: `${__dirname}/_source/blog/_posts/`,
      },
    },

    {
      resolve: `gatsby-transformer-remark`,
      options: {
        tableOfContents: {
          heading: null,
          maxDepth: 3,
        },
        plugins: [
          {
            resolve: `gatsby-remark-prismjs`,
            options: {},
          },
        ],
      },
    },
  ],
};
