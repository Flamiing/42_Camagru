DELETE FROM comments 
WHERE user_id NOT IN (
  'CrazyDuck89a3223adac770c55773',
  'AmazingUser1e3518a429342e47d81d',
  'flamiingb5f4678623452394707d'
);

DELETE FROM likes 
WHERE user_id NOT IN (
  'CrazyDuck89a3223adac770c55773',
  'AmazingUser1e3518a429342e47d81d',
  'flamiingb5f4678623452394707d'
);

DELETE FROM images 
WHERE user_id NOT IN (
  'CrazyDuck89a3223adac770c55773',
  'AmazingUser1e3518a429342e47d81d',
  'flamiingb5f4678623452394707d'
);

DELETE FROM users 
WHERE user_id NOT IN (
  'CrazyDuck89a3223adac770c55773',
  'AmazingUser1e3518a429342e47d81d',
  'flamiingb5f4678623452394707d'
);