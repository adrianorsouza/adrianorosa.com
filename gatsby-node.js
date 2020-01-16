const path = require('path');
const slugify = require('slugify');
const { createFilePath } = require(`gatsby-source-filesystem`);

exports.onCreateWebpackConfig = ({ stage, actions }) => {
  if (stage === `build-javascript`) {
    actions.setWebpackConfig({
      devtool: false,
    });
  }
};

exports.onCreateNode = ({ node, getNode, actions }) => {
  const { createNodeField } = actions;
  // Ensures we are processing only markdown files
  if (node.internal.type === 'MarkdownRemark') {
    const fileNode = getNode(node.parent);

    // const category = fileNode.relativeDirectory.replace(/^[0-9\-]{3}/, '');
    const category = node.frontmatter.category;

    // Grab the title from the frontmatter, otherwise grab from the first heading of markdown content
    const title = node.frontmatter.title || node.internal.content.split(`\n`)[0];
    // const slug = slugify(title, { lower: true });
    const slug = fileNode.name.replace(/^[0-9]{4}-[0-9]{2}-[0-9]{2}-/, '');

    // Creates new query'able field with name of 'title'
    createNodeField({
      node,
      name: 'title',
      value: title,
    });

    // Creates new query'able field with name of 'slug'
    createNodeField({
      node,
      name: 'slug',
      value: `/blog/${category}/${slug}.html`,
    });

    // Creates new query'able field with name of 'category'
    createNodeField({
      node,
      name: 'category',
      value: `${category}`,
    });
  }
};

// This can be used to deploy your content to the CDN, like so:
const assetsDirectory = `public`;
exports.onPostBuild = async function onPostBuild() {
  // do something with public
  // e.g. upload to S3
};

module.exports.createPages = async ({ graphql, actions }) => {
  const { createPage } = actions;

  const postsTemplate = path.resolve('./src/templates/posts.js');
  const res = await graphql(`
    query {
      allMarkdownRemark {
        edges {
          node {
            id
            frontmatter {
              title
              #updated_at
            }
            fields {
              title
              slug
              category
            }
            tableOfContents
            excerpt
            html
          }
          next {
            fields {
              title
              slug
            }
          }
          previous {
            fields {
              title
              slug
            }
          }
        }
      }
    }
  `);

  const { allMarkdownRemark } = res.data;
  allMarkdownRemark.edges.forEach(({ node, next, previous }) => {
    const { id, frontmatter, tableOfContents, excerpt, html } = node;
    const { title, slug } = node.fields;

    if (path) {
      createPage({
        path: slug,
        component: postsTemplate,
        context: {
          id,
          title: frontmatter.title || title,
          frontmatter,
          slug: slug,
          description: excerpt,
          content: html,
          tableOfContents,
          previous,
          next,
        },
      });
    }
  });
};
