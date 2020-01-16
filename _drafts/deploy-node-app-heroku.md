---
title: Heroku deploy nodejs app 
created_at: 2020-01-15 15:40
---

    mkdir project-name
    cd project-name
    git init
    echo "node_modules/" > .gitinore
    git add .gitinore 
    git commit -m "Initial Commit"
    
    heroku create my-project-name
    
    

### Create the project files 

File: server.js

```javascript
const express = require('express');
const app = express();
const path = require('path');

// Middleware
// app.use(express.json());

app.use(express.static(path.resolve(__dirname, 'public')));

const PORT = process.env.PORT || 5000;

app.listen(PORT, () => console.log(`Server started on port: ${PORT}`));
```

File: public/index.js

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Index</title>
</head>
<body>
<h1>hello world heroku!!</h1>
</body>
</html>

```

### Deploy to Heroku
    heroku create my-project-name
