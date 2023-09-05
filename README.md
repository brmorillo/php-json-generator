# php-json-generator
Um gerador de JSON feito em PHP.

Passo a passo para finalizar o projeto

1. Substituir os repeat() por for.
1.1. Substituir os index() por $i.

2. Substituir os integer() por Math.floor(Math.random() * (max - min + 1)) + min;
2.1. Substituir os floating() por (Math.random() * (max - min) + min).toFixed(precision);
2.2. Substituir os bool() por Math.random() >= 0.5;
2.3. Substituir os date() por new Date(Math.random() * (max - min) + min).toISOString();
2.4. Substituir os random() por Math.floor(Math.random() * (max - min + 1)) + min;

3. Substituir os lorem() por faker.lorem.[word, words, sentence, slug, sentences, paragraph, paragraphs, text, lines](count);
3.1. Substituir os firstName() por faker.name.firstName();
3.2. Substituir os surname() por faker.name.lastName();
3.3. Substituir os company() por faker.company.companyName();
3.4. Substituir os email() por faker.internet.email();
3.5. Substituir os phone() por faker.phone.phoneNumber();
3.6. Substituir os street() por faker.address.streetName();
3.7. Substituir os city() por faker.address.city();
3.8. Substituir os state() por faker.address.state();
3.9. Substituir os country() por faker.address.country();
3.10. Substituir os zip() por faker.address.zipCode();
3.11. Substituir os color() por faker.internet.color();
3.12. Substituir os url() por faker.internet.url();
3.13. Substituir os userName() por faker.internet.userName();
3.14. Substituir os domainName() por faker.internet.domainName();
3.15. Substituir os ipv6() por faker.internet.ipv6();
3.16. Substituir os ipv4() por faker.internet.ipv4();
3.17. Substituir os mac() por faker.internet.mac();
3.18. Substituir os uuid() por faker.random.uuid();
3.19. Substituir os word() por faker.random.word();
3.20. Substituir os words() por faker.random.words();
3.21. Substituir os sentence() por faker.random.sentence();
3.22. Substituir os slug() por faker.random.slug();
3.23. Substituir os sentences() por faker.random.sentences();
3.24. Substituir os paragraph() por faker.random.paragraph();
3.25. Substituir os paragraphs() por faker.random.paragraphs();
3.26. Substituir os text() por faker.random.text();
3.27. Substituir os lines() por faker.random.lines();
3.28. Substituir os firstName() por faker.name.firstName();
3.29. Substituir os surname() por faker.name.lastName();

4. Substituir os objectId() por faker.random.uuid();
4.1. Substituir os guid() por faker.random.uuid();
