/** ========================================================================
 * Project     : gatsby-markdown-starter
 * Component   : posts
 * Author      : Adriano Rosa <https://adrianorosa.com>
 * Date        : 2019-11-09 15:12
 * ========================================================================
 * Copyright 2019 Adriano Rosa <https://adrianorosa.com>
 * ======================================================================== */

import React from 'react';
import { Link } from 'gatsby';
import dayjs from 'dayjs';
import Layout from '../components/layout';
import styles from './posts.module.scss';

export default ({ pageContext }) => {
  const {
    title,
    frontmatter,
    tableOfContents,
    description,
    content,
    previous,
    next,
  } = pageContext;

  return (
    <Layout title={title} description={description}>
      <h1>{title}</h1>

      {frontmatter.updated_at && (
        <small>
          updated @{' '}
          {dayjs(frontmatter.updated_at).format('YYYY-MM-DD HH:mm:ss')}
        </small>
      )}

      <div dangerouslySetInnerHTML={{ __html: tableOfContents }} />

      <div dangerouslySetInnerHTML={{ __html: content }} />

      <nav className={styles.postNavFooter}>
        <div>
          {previous && (
            <Link to={previous.fields.slug}>
              {`<`} {previous.fields.title}
            </Link>
          )}
        </div>
        <div>home</div>
        <div>
          {next && (
            <Link to={next.fields.slug}>
              {next.fields.title} {`>`}
            </Link>
          )}
        </div>
      </nav>
    </Layout>
  );
};
